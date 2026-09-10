const assert = require("node:assert/strict");
const {
  existsSync,
  mkdirSync,
  mkdtempSync,
  readFileSync,
  rmSync,
  writeFileSync,
} = require("node:fs");
const { createRequire } = require("node:module");
const os = require("node:os");
const { spawnSync } = require("node:child_process");
const path = require("node:path");
const test = require("node:test");
const sass = require("sass");
const pkg = require("../package.json");
const projectConfig = require("../project.config");

test("central configuration keeps all stable Pages routes under the project base", () => {
  assert.equal(pkg.homepage, "https://chengchuu.github.io/mazey.css/");
  assert.equal(projectConfig.site.basePath, "/mazey.css/");
  assert.deepEqual(
    Object.values(projectConfig.site.pages).map((page) => page.url),
    [
      "https://chengchuu.github.io/mazey.css/",
      "https://chengchuu.github.io/mazey.css/playground/",
      "https://chengchuu.github.io/mazey.css/api/",
    ],
  );
});

test("website dependencies stay development-only at the required ranges", () => {
  const expected = {
    bootstrap: "^5.3.8",
    mazey: "^5.9.0",
    react: "^19.2.8",
    "react-dom": "^19.2.8",
  };
  assert.deepEqual(
    Object.fromEntries(
      Object.keys(expected).map((name) => [name, pkg.devDependencies[name]]),
    ),
    expected,
  );
  assert.equal(pkg.dependencies, undefined);
});

test("stylesheet configuration preserves the package root and named bundles", () => {
  assert.equal(pkg.main, "lib/index.css");
  assert.equal(projectConfig.stylesheets[0].importPath, "mazey.css");
  assert.deepEqual(
    projectConfig.stylesheets.map((entry) => entry.name),
    ["index", "base", "blogbase", "link", "confluence"],
  );
});

test("the package exposes the documented CSS and Sass entry points", () => {
  const root = path.resolve(__dirname, "..");
  const readme = readFileSync(path.join(root, "README.md"), "utf8");
  const apiPage = readFileSync(path.join(root, "site/api.html"), "utf8");
  assert.match(readme, /@use "pkg:mazey\.css\/extend\/base";/);
  assert.doesNotMatch(readme, /mazey\.css\/src\//);
  assert.match(readme, /sass --pkg-importer=node input\.scss output\.css/);
  assert.match(readme, /new sass\.NodePackageImporter\(\)/);
  assert.match(apiPage, /@use "pkg:mazey\.css\/extend\/base";/);
  assert.doesNotMatch(apiPage, /mazey\.css\/src\//);
  assert.match(apiPage, /sass --pkg-importer=node input\.scss output\.css/);
  assert.match(apiPage, /new sass\.NodePackageImporter\(\)/);
  assert.equal(pkg.devDependencies.sass, "^1.71.0");
  assert.deepEqual(pkg.exports, {
    ".": {
      style: "./lib/index.css",
      default: "./lib/index.css",
    },
    "./lib/*.css": {
      style: "./lib/*.css",
      default: "./lib/*.css",
    },
    "./lib/confluence.js": "./lib/confluence.js",
    "./extend/*.scss": {
      sass: "./sass/extend/*.scss",
    },
    "./function/*.scss": {
      sass: "./sass/function/*.scss",
    },
    "./mixin/*.scss": {
      sass: "./sass/mixin/*.scss",
    },
    "./variate/*.scss": {
      sass: "./sass/variate/*.scss",
    },
  });
});

test("the npm allowlist excludes generated website and package-build shims", () => {
  const root = path.resolve(__dirname, "..");
  assert.deepEqual(pkg.files, [
    "lib/*.css",
    "lib/confluence.js",
    "sass/**/*.scss",
    "README.md",
    "LICENSE",
  ]);
  assert.equal(pkg.files.includes("src/**/*.scss"), false);
  for (const family of ["extend", "function", "mixin", "variate"]) {
    assert.equal(existsSync(path.join(root, "src", family)), false);
    assert.equal(existsSync(path.join(root, "sass", family)), true);
  }
  for (const lifecycle of ["prepack", "postpack"]) {
    assert.equal(pkg.scripts[lifecycle], undefined);
  }
});

test("the packed package resolves and compiles public entries in isolation", () => {
  const root = path.resolve(__dirname, "..");
  const temporaryRoot = mkdtempSync(
    path.join(os.tmpdir(), "mazey-css-consumer-"),
  );
  const packDirectory = path.join(temporaryRoot, "pack");
  const consumerDirectory = path.join(temporaryRoot, "consumer");
  mkdirSync(packDirectory);
  mkdirSync(consumerDirectory);

  try {
    writeFileSync(
      path.join(consumerDirectory, "package.json"),
      JSON.stringify({ private: true }),
    );
    const npmEnvironment = {
      ...process.env,
      npm_config_cache: path.join(temporaryRoot, "npm-cache"),
    };
    const packResult = spawnSync(
      "npm",
      [
        "pack",
        "--json",
        "--ignore-scripts",
        "--pack-destination",
        packDirectory,
      ],
      { cwd: root, encoding: "utf8", env: npmEnvironment },
    );
    assert.equal(packResult.status, 0, packResult.stderr);
    const [packManifest] = JSON.parse(packResult.stdout);
    const packedPaths = packManifest.files.map((file) => file.path);
    assert.deepEqual(
      packedPaths.filter((file) => file.endsWith(".scss")).sort(),
      [
        "sass/extend/base.scss",
        "sass/extend/index.scss",
        "sass/extend/scroll.scss",
        "sass/extend/table.scss",
        "sass/function/index.scss",
        "sass/mixin/code.scss",
        "sass/mixin/index.scss",
        "sass/mixin/theme.scss",
        "sass/variate/index.scss",
      ],
    );
    assert.equal(
      packedPaths.some((file) => file.startsWith("src/")),
      false,
    );
    for (const artifact of [
      "lib/404.css",
      "lib/base.css",
      "lib/blogbase.css",
      "lib/confluence.css",
      "lib/confluence.js",
      "lib/index.css",
      "lib/link.css",
      "lib/tiny.css",
    ]) {
      assert.ok(packedPaths.includes(artifact), `${artifact} must be packed`);
    }
    assert.equal(
      packedPaths.some((file) =>
        /^(?:docs|dist-dev|examples|images|scripts|site|test)\//.test(file),
      ),
      false,
    );

    const tarball = path.join(packDirectory, packManifest.filename);
    const installResult = spawnSync(
      "npm",
      [
        "install",
        "--offline",
        "--ignore-scripts",
        "--no-audit",
        "--no-fund",
        "--package-lock=false",
        tarball,
      ],
      { cwd: consumerDirectory, encoding: "utf8", env: npmEnvironment },
    );
    assert.equal(installResult.status, 0, installResult.stderr);

    const consumerRequire = createRequire(
      path.join(consumerDirectory, "index.js"),
    );
    assert.match(consumerRequire.resolve("mazey.css"), /lib\/index\.css$/);
    assert.match(
      consumerRequire.resolve("mazey.css/lib/base.css"),
      /lib\/base\.css$/,
    );
    assert.match(
      consumerRequire.resolve("mazey.css/lib/confluence.js"),
      /lib\/confluence\.js$/,
    );

    for (const entry of [
      "extend/base",
      "extend/index",
      "extend/scroll",
      "extend/table",
      "function/index",
      "mixin/code",
      "mixin/index",
      "mixin/theme",
      "variate/index",
    ]) {
      assert.doesNotThrow(
        () =>
          sass.compileString(`@use "pkg:mazey.css/${entry}";`, {
            importers: [new sass.NodePackageImporter(consumerDirectory)],
          }),
        `${entry} must compile from the packed package`,
      );
    }

    const result = sass.compileString(
      '@use "pkg:mazey.css/extend/base";\n.example { @extend %m-flex-center; }\n',
      {
        importers: [new sass.NodePackageImporter(consumerDirectory)],
      },
    );
    assert.match(result.css, /display: flex/);
    assert.match(result.css, /justify-content: center/);
  } finally {
    rmSync(temporaryRoot, { recursive: true, force: true });
  }
});

test("pnpm is the only committed dependency lock format", () => {
  const root = path.resolve(__dirname, "..");
  const result = spawnSync(
    "git",
    ["ls-files", "--", "package-lock.json", "pnpm-lock.yaml"],
    { cwd: root, encoding: "utf8" },
  );
  assert.equal(result.status, 0, result.stderr);
  assert.deepEqual(result.stdout.trim().split("\n"), ["pnpm-lock.yaml"]);
});

const assert = require("node:assert/strict");
const { readFileSync } = require("node:fs");
const { spawnSync } = require("node:child_process");
const path = require("node:path");
const test = require("node:test");
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
    mazey: "^5.6.0",
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

test("the documented Sass placeholder example compiles", () => {
  const root = path.resolve(__dirname, "..");
  const readme = readFileSync(path.join(root, "README.md"), "utf8");
  assert.match(readme, /@use "mazey\.css\/src\/extend\/base";/);
  const result = spawnSync(
    process.execPath,
    [path.join(root, "node_modules/sass/sass.js"), "--stdin", "--load-path=.."],
    {
      cwd: root,
      encoding: "utf8",
      input:
        '@use "mazey.css/src/extend/base";\n.example { @extend %m-flex-center; }\n',
    },
  );
  assert.equal(result.status, 0, result.stderr);
  assert.match(result.stdout, /display: flex/);
  assert.match(result.stdout, /justify-content: center/);
});

test("the npm allowlist excludes generated website and package-build shims", () => {
  assert.deepEqual(pkg.files, [
    "lib/*.css",
    "lib/confluence.js",
    "src/**/*.scss",
    "README.md",
    "LICENSE",
  ]);
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

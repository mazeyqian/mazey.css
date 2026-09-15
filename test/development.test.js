const assert = require("node:assert/strict");
const fs = require("node:fs");
const path = require("node:path");
const test = require("node:test");

const projectRoot = path.resolve(__dirname, "..");

test("development server exposes every maintained source-backed library", () => {
  const previousEntry = process.env.ENTRY;
  process.env.ENTRY = "link";
  delete require.cache[require.resolve("../webpack.config")];
  delete require.cache[require.resolve("../webpack.config.dev")];
  const config = require("../webpack.config.dev");
  if (previousEntry === undefined) delete process.env.ENTRY;
  else process.env.ENTRY = previousEntry;
  delete require.cache[require.resolve("../webpack.config")];
  delete require.cache[require.resolve("../webpack.config.dev")];
  const packageJson = require("../package.json");
  assert.equal(
    packageJson.scripts.dev,
    "webpack serve --mode development --config webpack.config.dev.js",
  );
  assert.equal(packageJson.scripts["dev:link"], undefined);
  assert.match(
    packageJson.scripts.lint,
    /(?:^| )webpack\.config\.dev\.js(?: |$)/,
  );
  assert.deepEqual(Object.keys(config.entry), [
    "base",
    "blogbase",
    "confluence",
    "index",
    "link",
  ]);
  for (const name of Object.keys(config.entry)) {
    assert.equal(config.entry[name], `./src/z-temporary/${name}.js`);
  }
  assert.equal(config.output.filename, "[name].js");
  const extractPlugin = config.plugins.find(
    (plugin) => plugin.constructor.name === "MiniCssExtractPlugin",
  );
  assert.ok(extractPlugin);
  assert.equal(extractPlugin.options.filename, "[name].css");
  assert.equal(config.mode, "development");
  assert.equal(config.devServer.host, "127.0.0.1");
  assert.equal(config.devServer.port, 4132);
  assert.equal(config.devServer.static, false);
  assert.equal(config.devServer.hot, false);
  assert.equal(config.devServer.liveReload, false);
  assert.equal(config.devServer.client, false);
  assert.deepEqual(config.devServer.headers, {
    "Access-Control-Allow-Origin": "*",
    "Cross-Origin-Resource-Policy": "cross-origin",
  });
  assert.equal(config.devServer.devMiddleware.publicPath, "/");
});

test("only the Confluence entry contains library JavaScript", () => {
  for (const name of ["base", "blogbase", "index", "link"]) {
    const source = fs.readFileSync(
      path.join(projectRoot, `src/z-temporary/${name}.js`),
      "utf8",
    );
    assert.match(
      source,
      new RegExp(`^import ['"]\\.\\./z-style/${name}\\.scss['"];\\s*$`),
    );
  }
  const confluence = fs.readFileSync(
    path.join(projectRoot, "src/z-temporary/confluence.js"),
    "utf8",
  );
  assert.match(confluence, /if \(window\.\$\)/);
});

test("production single-entry filenames remain unchanged", () => {
  const configPath = require.resolve("../webpack.config");
  const previousEntry = process.env.ENTRY;

  for (const name of ["base", "blogbase", "confluence", "index", "link"]) {
    process.env.ENTRY = name;
    delete require.cache[configPath];
    const config = require(configPath);
    const extractPlugin = config.plugins.find(
      (plugin) => plugin.constructor.name === "MiniCssExtractPlugin",
    );

    assert.deepEqual(config.entry, {
      [name]: `./src/z-temporary/${name}.js`,
    });
    assert.equal(config.output.filename.replace("[name]", name), `${name}.js`);
    assert.equal(
      extractPlugin.options.filename.replace("[name]", name),
      `${name}.css`,
    );
  }

  if (previousEntry === undefined) delete process.env.ENTRY;
  else process.env.ENTRY = previousEntry;
  delete require.cache[configPath];
});

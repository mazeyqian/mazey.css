const assert = require("node:assert/strict");
const test = require("node:test");

test("Link stylesheet development server uses the sibling-project contract", () => {
  const previousEntry = process.env.ENTRY;
  process.env.ENTRY = "link";
  const config = require("../webpack.config.link.dev");
  if (previousEntry === undefined) delete process.env.ENTRY;
  else process.env.ENTRY = previousEntry;

  const packageJson = require("../package.json");
  assert.equal(
    packageJson.scripts["dev:link"],
    "cross-env ENTRY=link webpack serve --mode development --config webpack.config.link.dev.js",
  );
  assert.match(
    packageJson.scripts.lint,
    /(?:^| )webpack\.config\.link\.dev\.js(?: |$)/,
  );
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

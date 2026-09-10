const { existsSync, readFileSync } = require("node:fs");
const path = require("node:path");
const pkg = require("../package.json");
const projectConfig = require("../project.config");

const root = path.resolve(__dirname, "..");
const fail = (message) => {
  throw new Error(`Package validation failed: ${message}`);
};

if (pkg.main !== "lib/index.css")
  fail("package main must remain lib/index.css");
if (pkg.dependencies && Object.keys(pkg.dependencies).length)
  fail("website dependencies must not be runtime dependencies");
for (const entry of projectConfig.stylesheets) {
  const file = path.join(root, entry.path);
  if (!existsSync(file)) fail(`missing ${entry.path}`);
  if (readFileSync(file, "utf8").trim().length < 100)
    fail(`${entry.path} is unexpectedly small`);
}
if (!existsSync(path.join(root, "lib/confluence.js")))
  fail("missing lib/confluence.js");

console.log("Package artifacts are valid.");

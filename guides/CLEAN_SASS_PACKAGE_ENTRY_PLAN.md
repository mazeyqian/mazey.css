# Clean Sass Package Entry Plan

## Goal

Replace the internal-looking `mazey.css/src/...` Sass contract with explicit package entry points
for the next major release. Publish only the documented Sass utility modules from a maintained
top-level `sass/` directory and make this the canonical consumer syntax:

```scss
@use "pkg:mazey.css/extend/base";

.example {
  @extend %m-flex-center;
}
```

This is an intentional breaking change. Do not retain compatibility wrappers or aliases for
`mazey.css/src/...` imports.

This library will be published as a new npm package with no existing users or released versions.
For frontend code, avoid defensive programming unless it protects a real UI, network, storage, or third-party boundary. Do not add broad `try...catch`, redundant optional chaining, fallback UI states, default objects, or guards around internal data that the component contract already guarantees.
You are authorized to restructure the existing project, including changing its current file and directory organization, and to install, remove, or update npm packages when necessary to produce a cleaner, more maintainable, and production-ready implementation.

## Prerequisites

Before editing, read these sources in order:

1. `/Users/cheng/web/npm/AGENTS.md`
2. `/Users/cheng/web/npm/mazey.css/AGENTS.md`
3. `/Users/cheng/web/npm/mazey.css/package.json`
4. `/Users/cheng/web/npm/mazey.css/webpack.config.js`
5. `/Users/cheng/web/npm/mazey.css/scripts/validate-package.js`
6. `/Users/cheng/web/npm/mazey.css/test/project-config.test.js`
7. `/Users/cheng/web/npm/mazey.css/README.md`
8. `/Users/cheng/web/npm/mazey.css/site/api.html`

Inspect `git status --short` before editing. Preserve all unrelated staged, unstaged, and untracked
work. Do not change the package version, commit, tag, publish, or deploy.

## Confirmed decisions

- Move only the documented utility families: `extend`, `function`, `mixin`, and `variate`.
- Preserve the existing `variate` name. Do not combine this migration with a terminology rename.
- Keep `src/z-style/` and `src/z-temporary/` as private package-build sources.
- Use Sass conditional exports and `pkg:` URLs as the public contract.
- Require Dart Sass 1.71 or later and an enabled Node package importer.
- Do not add copy, move, `prepack`, `postpack`, or cleanup scripts. The tracked `sass/` tree must be
  the only source of truth for published Sass utilities.
- Do not publish any `src/**/*.scss` file after the migration.

## Package layout

Move the four public module directories without changing their internal filenames:

```text
sass/
├── extend/
├── function/
├── mixin/
└── variate/

src/
├── z-style/
└── z-temporary/
```

Update private stylesheet entries to import the moved modules through correct relative filesystem
paths. In particular, update the maintained imports in `base.scss`, `blogbase.scss`, `link.scss`,
and `confluence.scss`. Do not edit generated `lib` files by hand.

## Package manifest

Update `package.json#files` to publish `sass/**/*.scss` instead of `src/**/*.scss`. Keep the existing
compiled CSS, `lib/confluence.js`, README, and license entries.

Add an `exports` map with these exact public surfaces:

```json
{
  ".": {
    "style": "./lib/index.css",
    "default": "./lib/index.css"
  },
  "./lib/*.css": {
    "style": "./lib/*.css",
    "default": "./lib/*.css"
  },
  "./lib/confluence.js": "./lib/confluence.js",
  "./extend/*.scss": {
    "sass": "./sass/extend/*.scss"
  },
  "./function/*.scss": {
    "sass": "./sass/function/*.scss"
  },
  "./mixin/*.scss": {
    "sass": "./sass/mixin/*.scss"
  },
  "./variate/*.scss": {
    "sass": "./sass/variate/*.scss"
  }
}
```

Keep `main` set to `lib/index.css`. Raise the `sass` development range from `^1.58.3` to
`^1.71.0` or a newer compatible range already selected by the lockfile. Regenerate
`pnpm-lock.yaml` with pnpm; do not create `package-lock.json`.

The export keys must retain the `.scss` suffix even though consumers omit it from `pkg:` URLs. Sass
uses the `sass` condition and resolves the canonical example to `sass/extend/base.scss`. Keep
`default` after `style` in each conditional CSS export.

## Documentation and repository guidance

Update the README and maintained API-page source to:

- replace every `mazey.css/src/...` example with `pkg:mazey.css/...`;
- describe `sass/` as the published utility-module tree;
- state that consumers need Dart Sass 1.71 or later;
- state that Sass CLI users enable resolution with `--pkg-importer=node`, while JavaScript API users
  configure `new sass.NodePackageImporter()`;
- keep compiled CSS imports as the simplest integration when consumers do not need Sass members.

Update `AGENTS.md` so its repository map, package contract, source/generated boundaries, and
validation instructions match the new layout. Do not edit generated website output under `docs/`.

## Tests

Update the existing package-contract tests to assert:

- the README contains `@use "pkg:mazey.css/extend/base"` and no `mazey.css/src/` import;
- the package allowlist contains `sass/**/*.scss` and excludes `src/**/*.scss`;
- the complete `exports` object preserves the root CSS entry, every `lib/*.css` subpath,
  `lib/confluence.js`, and all four Sass utility patterns;
- the canonical Sass example compiles through `NodePackageImporter` and still emits
  `display: flex` and `justify-content: center` for `%m-flex-center`.

Do not validate only against repository-relative load paths. Create an isolated temporary consumer,
install the locally packed tarball without network dependencies or lifecycle scripts, and compile
the canonical example with the Node package importer. Keep all temporary package files and lockfiles
outside the repository.

## Validation

Run these checks after implementation:

```bash
pnpm install
npm run preview
npm pack --dry-run --json
git diff --check
git status --short
```

Inspect the pack manifest and verify all of these conditions:

- every public Sass file is under `sass/`;
- no `src/**/*.scss` or `src/z-temporary/*.js` file is included;
- `lib/404.css` and `lib/tiny.css` remain published compatibility artifacts;
- the five current source-backed CSS bundles and `lib/confluence.js` remain present;
- website, playground, test, script, image, configuration, `docs`, and `dist-dev` files remain absent.

Review the complete diff after rebuilding. The move and import-path changes must not alter compiled
CSS or `lib/confluence.js` behavior. If the installed Sass version rewrites equivalent generated CSS
syntax, separate that tool-version drift from this package-entry migration rather than accepting it
without review.

## Acceptance criteria

- A clean consumer can compile `@use "pkg:mazey.css/extend/base"` from the packed artifact.
- The old `mazey.css/src/...` contract is absent from package contents, exports, tests, and maintained
  documentation.
- Existing documented CSS imports and `lib/confluence.js` continue to resolve through the exports
  map.
- No generated Sass copy, pack lifecycle script, compatibility wrapper, or second source tree is
  introduced.
- The full repository preview, package validation, clean-consumer check, pack inspection, formatting,
  and diff checks pass before handoff.

## References

- [Sass Node package importer](https://sass-lang.com/documentation/js-api/classes/nodepackageimporter/)
- [Sass `pkg:` URLs](https://sass-lang.com/documentation/at-rules/use/#pkg-urls)
- [webpack package exports](https://webpack.js.org/guides/package-exports/)

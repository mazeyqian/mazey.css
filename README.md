<!-- omit from toc -->

# mazey.css

[![npm version][npm-image]][npm-url]
[![license][license-image]][license-url]

Reusable CSS and Sass styles for normalized pages, responsive layouts, links, and Confluence content.

- [Website](https://chengchuu.github.io/mazey.css/)
- [Playground](https://chengchuu.github.io/mazey.css/playground/)
- [Stylesheet API reference](https://chengchuu.github.io/mazey.css/api/)

[npm-image]: https://img.shields.io/npm/v/mazey.css
[npm-url]: https://www.npmjs.com/package/mazey.css
[license-image]: https://img.shields.io/npm/l/mazey.css
[license-url]: https://github.com/chengchuu/mazey.css/blob/main/LICENSE

## Install

```bash
npm install mazey.css
```

## Usage

Import the package root to apply normalized browser defaults:

```js
import "mazey.css";
```

Additional compiled stylesheets are available as explicit package paths:

```js
import "mazey.css/lib/base.css";
import "mazey.css/lib/blogbase.css";
import "mazey.css/lib/link.css";
import "mazey.css/lib/confluence.css";
```

| Entry                          | Purpose                                              |
| ------------------------------ | ---------------------------------------------------- |
| `mazey.css`                    | Package-root normalization stylesheet                |
| `mazey.css/lib/base.css`       | Responsive base layout and semantic status variants  |
| `mazey.css/lib/blogbase.css`   | Blog color variables, header, footer, and dark theme |
| `mazey.css/lib/link.css`       | Compact link-list and `tiny-box` presentation        |
| `mazey.css/lib/confluence.css` | Confluence-oriented document styles                  |

The package also publishes its Sass source. For example:

```scss
@use "mazey.css/src/extend/base";

.example {
  @extend %m-flex-center;
}
```

### Confluence

Apply `lib/confluence.css` to pages containing `.wiki-content` or `.entry-content`. The optional
`lib/confluence.js` enhancement expects jQuery to be available on the page; the stylesheet itself
does not require JavaScript.

## Development

```bash
pnpm install
npm run typecheck
npm run lint
npm test
npm run build
npm run docs
npm run format:check
```

`npm run build` regenerates the current package artifacts under `lib/`. `npm run docs` builds the
homepage, playground, stylesheet API reference, manifest, service worker, crawler files, and final
GitHub Pages artifact under `docs/`.

The Pages workflow deploys pushes to `main` and `release/v*`. The npm workflow publishes only from
`release/v*` after the full validation pipeline passes.

## License

This software is released under the terms of the [GPL-2.0 license][license-url].

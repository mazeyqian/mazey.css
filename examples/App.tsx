import { useEffect, useMemo, useState } from "react";
import { siteRuntimeConfig } from "../site/runtime-config";

const bundles = ["index", "base", "blogbase", "link", "confluence"] as const;
type Bundle = (typeof bundles)[number];

const samples: Record<Bundle, string> = {
  index:
    '<main><h1>Normalized document</h1><p>The package root applies consistent browser defaults.</p><button type="button">A native button</button><ul><li>Semantic list item</li><li>Another item</li></ul></main>',
  base: '<main class="base base-success"><h1>Successful operation</h1><p>The base bundle provides a responsive card layout and semantic border variants.</p></main>',
  blogbase:
    '<header class="site-header"><h1>Blog header</h1></header><main><p>Blog content uses the bundle color variables.</p></main><footer class="site-footer"><div class="bottom-bar">Footer bar</div></footer>',
  link: '<main><div class="tiny-box"><a href="#first">First useful link</a><a href="#second">Second useful link</a></div></main>',
  confluence:
    '<main class="wiki-content"><h1>Confluence content</h1><p>Document-focused table and code styles.</p><table><thead><tr><th>Bundle</th><th>Purpose</th></tr></thead><tbody><tr><td>confluence</td><td>Rendered documentation</td></tr></tbody></table><pre><code>npm install mazey.css</code></pre></main>',
};

function documentHtml(bundle: Bundle, css: string): string {
  const body = samples[bundle];
  return `<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><style>${css}</style><style>body{padding:1.5rem}a{display:block;margin:.5rem 0}</style></head><body>${body}</body></html>`;
}

export function App() {
  const [bundle, setBundle] = useState<Bundle>("index");
  const [css, setCss] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const controller = new AbortController();
    setLoading(true);
    setError("");
    fetch(`${siteRuntimeConfig.basePath}package-styles/${bundle}.css`, {
      signal: controller.signal,
    })
      .then((response) => {
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return response.text();
      })
      .then(setCss)
      .catch((reason: unknown) => {
        if (controller.signal.aborted) return;
        setError(
          `The ${bundle} stylesheet could not be loaded: ${reason instanceof Error ? reason.message : "unknown error"}.`,
        );
      })
      .finally(() => {
        if (!controller.signal.aborted) setLoading(false);
      });
    return () => controller.abort();
  }, [bundle]);

  const preview = useMemo(() => documentHtml(bundle, css), [bundle, css]);

  return (
    <div className="playground-grid mt-4">
      <section className="card" aria-labelledby="controls-title">
        <div className="card-body">
          <h2 className="h4" id="controls-title">
            Preview controls
          </h2>
          <label className="form-label" htmlFor="bundle">
            Public stylesheet bundle
          </label>
          <select
            className="form-select"
            id="bundle"
            value={bundle}
            onChange={(event) => setBundle(event.target.value as Bundle)}
          >
            {bundles.map((name) => (
              <option key={name} value={name}>
                {name === "index" ? "index (package root)" : name}
              </option>
            ))}
          </select>
          <p className="small text-body-secondary mt-3 mb-0">
            <code>
              {bundle === "index"
                ? 'import "mazey.css";'
                : `import "mazey.css/lib/${bundle}.css";`}
            </code>
          </p>
          <div className="mt-3" role="status" aria-live="polite">
            {loading && <span>Loading {bundle}.css…</span>}
            {error && <div className="alert alert-danger mb-0">{error}</div>}
            {!loading && !error && (
              <span className="text-success">Loaded {bundle}.css.</span>
            )}
          </div>
        </div>
      </section>
      <section aria-labelledby="preview-title">
        <h2 className="h4" id="preview-title">
          Isolated preview
        </h2>
        <iframe
          className="preview-frame"
          title={`${bundle} stylesheet preview`}
          sandbox=""
          srcDoc={preview}
        />
      </section>
    </div>
  );
}

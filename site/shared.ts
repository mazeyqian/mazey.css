import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "./site.css";
import { initializePwa } from "./pwa";
import { siteRuntimeConfig } from "./runtime-config";
import { initializeTheme } from "./theme";

initializeTheme(siteRuntimeConfig.themeStorageKey);
initializePwa(siteRuntimeConfig.pwa);

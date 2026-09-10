export interface SiteRuntimeConfig {
  basePath: string;
  packageName: string;
  themeStorageKey: string;
  pwa: {
    appName: string;
    enabled: boolean;
    scope: string;
    serviceWorkerUrl: string;
  };
}

declare const __SITE_RUNTIME_CONFIG__: SiteRuntimeConfig;

export const siteRuntimeConfig = __SITE_RUNTIME_CONFIG__;

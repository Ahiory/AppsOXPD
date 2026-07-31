function resolveApiBaseUrl() {
  const hostname = window.location.hostname;

  if (!hostname || hostname === "localhost" || hostname === "127.0.0.1") {
    return "http://localhost:3000";
  }

  return `${window.location.protocol}//${hostname}:3000`;
}

const defaultConfig = {
  apiBaseUrl: resolveApiBaseUrl(),
  maxFileSizeMb: 20
};

export const apiConfig = {
  ...defaultConfig,
  ...(window.HP_PRESMED_CONFIG || {})
};

export function apiUrl(path) {
  return `${apiConfig.apiBaseUrl.replace(/\/$/, "")}${path}`;
}

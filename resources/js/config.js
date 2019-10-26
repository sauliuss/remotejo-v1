var api = '';

switch (process.env.NODE_ENV) {
  case 'development':
    api = 'http://remotejoscrapper.test/api/v1';
  break;
  case 'production':
    api = 'https://remotejo.com/api/v1';
  break;
}

export const APP_CONFIG = {
  API_URL: api
}
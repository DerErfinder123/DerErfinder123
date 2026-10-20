const get = (k, def) => (process.env[k] !== undefined ? process.env[k] : def);

module.exports = {
  port: Number(get('PORT', 3000)),
  nodeEnv: get('NODE_ENV', 'development'),
  host: get('HOST', '0.0.0.0')
};

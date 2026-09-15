FROM node:18-alpine
WORKDIR /app

# Kopiere nur package.json zuerst für schnelleres Caching
COPY package*.json ./
RUN npm install --production

# Kopiere restlichen Code
COPY . .

EXPOSE 3000
CMD ["node", "index.js"]

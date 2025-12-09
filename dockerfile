FROM node:20 AS node-builder
WORKDIR /app

COPY package*.json vite.config.js ./
RUN npm install

COPY resources ./resources
COPY public/assets/css ./public/assets/css   # Copier le CSS nécessaire
RUN npm run build

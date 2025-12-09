# Stage 1 : build frontend (Vite)
FROM node:20 AS node-builder
WORKDIR /app

# Copier package.json + vite.config.js
COPY package*.json vite.config.js ./

# Installer dépendances
RUN npm install

# Copier le frontend
COPY resources ./resources
# Ne pas copier 'src' si il n'existe pas

# Builder assets
RUN npm run build

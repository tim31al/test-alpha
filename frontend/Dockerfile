FROM node:14.17.1-slim as build-stage

WORKDIR /app

COPY package*.json ./

RUN npm install

# копируем файлы и каталоги проекта в текущий рабочий каталог
COPY . .

RUN npm run build


FROM nginx:stable-alpine as production-stage
COPY --from=build-stage /app/dist /usr/share/nginx/html
CMD ["nginx", "-g", "daemon off;"]

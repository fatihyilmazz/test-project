FROM nginx:alpine

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./sites/ /etc/nginx/sites-available
COPY ./default.conf/ /etc/nginx/conf.d/default.conf
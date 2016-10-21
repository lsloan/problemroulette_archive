FROM tutum/lamp:latest
RUN rm -rf /app && mkdir /app
WORKDIR /app


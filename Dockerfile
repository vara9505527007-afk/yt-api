FROM php:8.2-cli

RUN apt update && apt install -y \
    ffmpeg \
    python3 \
    python3-pip \
    curl \
    && rm -rf /var/lib/apt/lists/*

RUN pip3 install yt-dlp

WORKDIR /app
COPY . .

EXPOSE 8080

CMD php -S 0.0.0.0:8080

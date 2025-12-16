FROM php:8.2-cli

RUN apt update && apt install -y \
    ffmpeg \
    python3 \
    python3-pip \
    python3-venv \
    pipx \
    curl \
    && rm -rf /var/lib/apt/lists/*

RUN pipx install yt-dlp
ENV PATH="/root/.local/bin:$PATH"

WORKDIR /app
COPY . .

EXPOSE 8080
CMD php -S 0.0.0.0:8080

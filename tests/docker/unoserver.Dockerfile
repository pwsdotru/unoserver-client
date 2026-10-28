FROM eclipse-temurin:27_35-jdk-resolute

ARG BUILD_CONTEXT="build-context"
ARG UID=worker
ARG GID=worker
# renovate: pypi: unoserver
ARG VERSION_UNOSERVER=3.7

LABEL org.opencontainers.image.title="unoserver-docker"
LABEL org.opencontainers.image.description="Container image that contains unoserver and libreoffice including large set of fonts for file format conversions"
LABEL org.opencontainers.image.licenses="MIT"
LABEL org.opencontainers.image.documentation="https://github.com/unoconv/unoserver-docker/blob/main/README.adoc"
LABEL org.opencontainers.image.source="https://github.com/unoconv/unoserver-docker"
LABEL org.opencontainers.image.url="https://github.com/unoconv/unoserver-docker"

WORKDIR /

RUN addgroup ${GID} && adduser --ingroup ${GID} ${UID}

RUN apt-get update

RUN apt-get install -y \
    bash curl \
    libreoffice \
    supervisor

RUN apt-get install -y \
    python3 \
    python3-pip

# fonts - https://wiki.alpinelinux.org/wiki/Fonts
#RUN apt-get install -y \
#    font-noto font-noto-cjk font-noto-extra \
#    terminus-font \
#    ttf-font-awesome \
#    ttf-dejavu \
#    ttf-freefont \
#    ttf-hack \
#    ttf-inconsolata \
#    ttf-liberation \
#    ttf-mononoki  \
#    ttf-opensans   \
#    fontconfig && \
#    fc-cache -f



RUN rm -rf /var/lib/apt/lists/* /tmp/*

# https://github.com/unoconv/unoserver/
RUN pip install --break-system-packages -U unoserver==${VERSION_UNOSERVER}

# setup supervisor
COPY --chown=${UID}:${GID} ${BUILD_CONTEXT} /
RUN chmod +x entrypoint.sh && \
    #    mkdir -p /var/log/supervisor && \
    #    chown ${UID}:${GID} /var/log/supervisor && \
    #    mkdir -p /var/run && \
    chown -R ${UID}:0 /run && \
    chmod -R g=u /run

USER ${UID}
WORKDIR /home/worker
ENV HOME="/home/worker"

VOLUME ["/data"]
EXPOSE 2003
EXPOSE 2002
ENTRYPOINT ["/entrypoint.sh"]

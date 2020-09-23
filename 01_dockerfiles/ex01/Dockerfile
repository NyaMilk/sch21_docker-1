FROM debian

ENV TS3SERVER_LICENSE=accept

RUN apt-get update && \
	apt-get upgrade -y && \
	apt-get install -y wget bzip2 && \
	wget https://files.teamspeak-services.com/releases/server/3.11.0/teamspeak3-server_linux_amd64-3.11.0.tar.bz2 && \
	tar -jxvf teamspeak3-server_linux_amd64-3.11.0.tar.bz2 && \
	rm -rf teamspeak3-server_linux_amd64-3.11.0.tar.bz2 && \
	mv teamspeak3-server_linux_amd64 /home/teamspeak3-server_linux_amd64 && \
	rm -rf /var/lib/apt/lists/*

WORKDIR /home/teamspeak3-server_linux_amd64

EXPOSE 9987/udp 10011 30033

ENTRYPOINT ["bash", "ts3server_minimal_runscript.sh"]

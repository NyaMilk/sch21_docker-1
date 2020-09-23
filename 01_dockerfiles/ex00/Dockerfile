FROM alpine

RUN apk update && \
	apk upgrade && \
	apk add vim

RUN mkdir /home/vim

WORKDIR /home/vim

ENTRYPOINT ["vim"]

FROM ruby:latest

RUN apt-get update && \
	apt-get upgrade -y && \
	apt-get install -y nodejs sqlite3 && \
	rm -rf /var/lib/apt/lists/*

RUN gem install bundler rails

ONBUILD COPY app /opt/app

ONBUILD WORKDIR /opt/app

ONBUILD EXPOSE 3000

ONBUILD RUN bundle update --bundler
ONBUILD RUN bundle install
ONBUILD RUN rails db:migrate
ONBUILD RUN rails db:seed

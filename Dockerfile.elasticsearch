FROM openjdk:alpine
# use alpine, much smaller than ubuntu

# just add bash here
RUN apk -U add bash curl tar
# avoid conflicts with debian host systems when mounting to host volume
ARG ES_VERSION
RUN wget -O- https://download.elastic.co/elasticsearch/release/org/elasticsearch/distribution/tar/elasticsearch/$ES_VERSION/elasticsearch-$ES_VERSION.tar.gz | tar xz -C usr/share
RUN echo "ES_VERSION=$ES_VERSION"

ENV ES_HOME=/usr/share/elasticsearch-$ES_VERSION \
    ES_JAVA_OPTS="-Xms1g -Xmx1g" \
    ES_VERSION=$ES_VERSION

USER root
RUN set -x ; \
	addgroup -g 1100 -S elasticsearch ; \
	adduser -u 1100 -D -S -G elasticsearch elasticsearch && exit 0 ; exit 1
RUN chown elasticsearch:elasticsearch -R $ES_HOME 
USER elasticsearch


HEALTHCHECK --timeout=5s CMD wget -q -O - http://localhost:9200/_cat/health

# chyno image plugin
RUN mkdir /tmp/es-image-plugin
ENV IMG_PLUGIN_SRC=/tmp/es-image-plugin
RUN wget -O- https://github.com/rastislav-chynoransky/elasticsearch-image/archive/master.tar.gz | tar xz -C $IMG_PLUGIN_SRC --strip-components=1
WORKDIR $IMG_PLUGIN_SRC
RUN chmod +x gradlew ; \
	./gradlew plugin ; \
	mkdir -p $ES_HOME/plugins/elasticsearch-image-$ES_VERSION ; \
	unzip build/distributions/elasticsearch-image-2.4.1.zip -d $ES_HOME/plugins/elasticsearch-image-$ES_VERSION	; \
	rm -Rf $IMG_PLUGIN_SRC

WORKDIR $ES_HOME
# in our usual version of es (2.4.1) plugin install command is found as option to 'plugin' command in bin
# later versions of elasticsearch have a seperate command plugin-install
RUN ./bin/plugin install https://github.com/vhyza/elasticsearch-analysis-lemmagen/releases/download/v$ES_VERSION/elasticsearch-analysis-lemmagen-$ES_VERSION-plugin.zip ; \
	mkdir config/lemmagen ; \
	wget -P config/lemmagen/ https://github.com/vhyza/lemmagen-lexicons/raw/master/free/lexicons/sk.lem ; \
	wget -P config/lemmagen/ https://github.com/vhyza/lemmagen-lexicons/raw/master/free/lexicons/cs.lem ; \
	wget -P config/lemmagen/ https://github.com/vhyza/lemmagen-lexicons/raw/master/free/lexicons/en.lem

# grab sng elasticsearch repository - will make proper directory structure inside config
# TODO: add all these files to a repo that gets downloaded in one go to simplify
# or even better: add the files	 to this repo and link it to the elasticsearch container as a volume
RUN wget -O- https://github.com/SlovakNationalGallery/elasticsearch-slovencina/archive/master.tar.gz | tar xz -C config --strip-components=1	


# grab extra synonym files and save them inside config/synonyms
RUN wget -O config/synonyms/synonyms_cz.txt https://sites.google.com/site/kevinbouge/synonyms-lists/synonyms_cz.txt
RUN wget -O- http://wordnetcode.princeton.edu/3.0/WNprolog-3.0.tar.gz | tar xz -C config/synonyms --strip-components=1 prolog/wn_s.pl

# grab extra stop-words files
RUN wget -O config/stop-words/stop-words-czech2.txt https://sites.google.com/site/kevinbouge/stopwords-lists/stopwords_cz.txt




EXPOSE 9200 9300
CMD ${ES_HOME}/bin/elasticsearch
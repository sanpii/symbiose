ifneq ("$(wildcard .env)","")
	include .env
endif

BOWER_FLAGS=
COMPOSER_FLAGS=--no-interaction
SYMFONY_FLAGS=--no-interaction

ifneq ($(APP_ENV),dev)
	BOWER_FLAGS+=--production
	COMPOSER_FLAGS+=--prefer-dist --no-dev --classmap-authoritative
endif

TASKS=
ifneq ("$(wildcard composer.json)","")
	TASKS+=vendor
endif

ifneq ("$(wildcard bower.json)","")
	TASKS+=assets
endif

ifneq ("$(wildcard bin/console)","")
	TASKS+=cache
endif

all: $(TASKS)

vendor: composer.lock

composer.lock: composer.json
	composer install $(COMPOSER_FLAGS)

assets: public/lib
	bin/console cache:clear $(SYMFONY_FLAGS)

public/lib: bower.json
	bower install $(BOWER_FLAGS)

cache:
	bin/console cache:warmup $(SYMFONY_FLAGS)

distclean:
	rm -rf vendor composer.lock src/Resources/public/lib

.PHONY: all assets cache distclean

ifneq ("$(wildcard .env)","")
	include .env
endif

BOWER_FLAGS=
COMPOSER_FLAGS=--no-interaction
SYMFONY_FLAGS=--no-interaction

ifeq ($(APP_ENV),prod)
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

assets: src/Resources/public/lib
	bin/console cache:clear $(SYMFONY_FLAGS)
	bin/console assets:install $(SYMFONY_FLAGS) --symlink --relative web
	bin/console assetic:dump $(SYMFONY_FLAGS)

src/Resources/public/lib: bower.json
	bower install $(BOWER_FLAGS)

cache:
	bin/console cache:warmup $(SYMFONY_FLAGS)

distclean:
	rm -rf vendor composer.lock src/Resources/public/lib

.PHONY: all assets cache distclean

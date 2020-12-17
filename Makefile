#!make
.PHONY: prepare grab-codes parse-urls grab-data build-json clean
prepare:
	composer install
	apt install -y wget2

all: prepare grab-codes parse-urls grab-data build-json
grab-codes:
	wget  --recursive -e robots=off -U mozilla  \
    --page-requisites --html-extension --convert-links \
	--domains banksifsccode.com \
	--no-parent \
	-I '/swift-codes,/swift-code' \
	'https://banksifsccode.com/swift-codes/'

parse-urls:
	grep -oh "https://banksifsccode\.com/swift-code/\w*/" -r banksifsccode.com/ | sort --unique > urls.txt

grab-data:
	rm -rf pages; mkdir -p pages; cd pages; wget2 -i ../urls.txt

build-json:
	php src/parse.php > entries.json

clean:
    rm -rf banksifsccode.com pages urls.txt

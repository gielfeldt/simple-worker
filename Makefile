all: clean test coverage

clean:
	rm -rf build/artifacts/*

test:
	phpunit --testsuite=simple-worker $(TEST)

coverage:
	phpunit --testsuite=simple-worker --coverage-html=build/artifacts/coverage $(TEST)

coverage-show:
	open build/artifacts/coverage/index.html

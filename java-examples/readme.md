You need to download maven to compile and run this project

    mvn dependency:copy-dependencies compile
    java -cp 'target/classes:target/dependency/*' test.hello

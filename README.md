tweetproxy  
=========  
  
install:  
composer install  
.bin/console tweetproxy:install  
  
twitter app creds (config.yml):  
tweet_proxy:  
    consumer_key: insert key  
    consumer_secret: insert secret  
    token: insert token  
    token_secret: insert secret  
    fetch_count: count #default: 20  
    display_count: count #default: 20  
  
login creds:  
user: tweetproxy@example.com  
pass: tweetproxy  

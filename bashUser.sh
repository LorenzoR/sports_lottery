#!/bin/bash 

while IFS=';' read dni firstname lastname email businessUnit workplace username; do
	php app/console fos:user:create "$username" "$email" "$dni" "$firstname" "$lastname" "$dni" "$businessUnit" "$workplace"
 
    #php app/console fos:user:create "$username" "$email" "$username"
done < mailsACA2.txt

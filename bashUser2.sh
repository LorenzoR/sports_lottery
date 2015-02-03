#!/bin/bash 

         COUNTER=100
         
	while [  $COUNTER -lt 1500 ]; do
		baseUsername='test'
		username=$baseUsername$COUNTER
             	email=$username$'@gmail.com'
		php app/console fos:user:create "$username" "$email" "$username"
		let COUNTER=COUNTER+1 
         done

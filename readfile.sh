#!/bin/sh
  echo enter file name
  read fname
   
   exec<$fname
   value=0
   while read line
   do
            value=`expr $value + 1`;
            echo $value;
   done
   echo "****$value";

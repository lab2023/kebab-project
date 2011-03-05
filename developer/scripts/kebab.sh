#!/bin/bash
# Set Parameters from Config File
CONFIG_FILE=kebab.conf

if [ ! -f $CONFIG_FILE ] ; then
# If Config File missing
	echo "Config File $CONFIG_FILE is missing"
	exit 1
else
. "$CONFIG_FILE"
fi

# First Argument is Layout Template
LAYOUT_TEMPLATE=$1

# Second Argument is Application Name
APPLICATION_NAME=$2

# Third Argument is Department (optional)
DEPARTMENT=$3

if [ $# -lt 2 ] ; then
# If needed parameters not set
	echo "Usage: $0 layout-template applicationName [department]"
	exit 1
fi

if [ ! -d $APPDIR ] ; then
# If Application directory is missing
	echo "Application directory $APPDIR is missing"
	echo "Set Application directory in $CONFIG_FILE first"
	exit 1
elif [ ! -d $TEMPLATESDIR ] ; then
# If Layout Templates directory is missing
	echo "Layout Template directory $TEMPLATESDIR is missing"
	echo "Set Layout Template directory in $CONFIG_FILE first"
	exit 1
elif [ ! -f $TEMPLATESDIR/$LAYOUT_TEMPLATE.tar.gz ] ; then
# If Layout Template does not exist
	echo "Layout $LAYOUT_TEMPLATE does not exist"
	exit 1
fi

# Change directory to Application directory
cd $APPDIR

if [ "$DEPARTMENT" != "" ] ; then
# If department is used
	if [ ! -d $DEPARTMENT ] ; then
	# If department directory does not exist
		mkdir $DEPARTMENT
	fi
	# Change directory to Department directory
	cd $DEPARTMENT
fi

# UnCompressing Layout Template
tar -zxf $TEMPLATESDIR/$LAYOUT_TEMPLATE.tar.gz

# Changing Application Name in all files
for file in `ls "$LAYOUT_TEMPLATE"/*/*.*` ; do
sed -e 's/{APP_NAME}/'"$APPLICATION_NAME"'/g' "$file" > tmp_file
mv tmp_file "$file"
done


APPLICATION_NAME=`echo $APPLICATION_NAME | sed -e 's/\([A-Z]\)/-\l\1/g'`
if [ ! -d $APPLICATION_NAME ] ; then
	# Renaming Application Directory
	mv $LAYOUT_TEMPLATE $APPLICATION_NAME
else
	# Application Directory already exist
	echo "$APPLICATION_NAME already exists. "
	echo "Application is created as layout template name: $LAYOUT_TEMPLATE"
	echo "You may manually complete the last step. "
	echo "Last step : Rename directory with your application name."
fi




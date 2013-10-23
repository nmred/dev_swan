TARGET0 = /usr/local/dev_swan/lib/

SUBDIRS = xml iterator create_makefile
INC_SRC0 = sw_xml.class.php. sw_create_makefile.class.php. sw_create_database.class.php. sw_create_database_wiki.class.php. sw_ftp.class.php. sw_exception.class.php.
 
INSTALL0 = /usr/bin/install -m 644 -o swan -g swan $< $(TARGET0)


all:
sw_xml.class.php.: sw_xml.class.php
	$(INSTALL0)
sw_create_makefile.class.php.: sw_create_makefile.class.php
	$(INSTALL0)
sw_create_database.class.php.: sw_create_database.class.php
	$(INSTALL0)
sw_create_database_wiki.class.php.: sw_create_database_wiki.class.php
	$(INSTALL0)
sw_ftp.class.php.: sw_ftp.class.php
	$(INSTALL0)
sw_exception.class.php.: sw_exception.class.php
	$(INSTALL0)


INS_DIRS = \
	if test "$(SUBDIRS)"; then \
		echo "Install Dirs:$(SUBDIRS)"; \
		for i in `echo $(SUBDIRS)`; do \
			make install -C $$i; \
		done; \
	fi; 

make_dir0:
	-@if test ! -d $(TARGET0); then \
	echo "Make Dir:  $(TARGET0)"; \
	mkdir -m 755 $(TARGET0); \
	chown swan:swan $(TARGET0); \
	fi;


install: make_dir0 $(INC_SRC0) 	
	@$(INS_DIRS)
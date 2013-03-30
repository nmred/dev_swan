TARGET0 = /usr/local/dev_swan/opt/etc

SUBDIRS = extra
INC_SRC0 = php.ini. my.cnf. sw_init_config. httpd.conf.
 
INSTALL0 = /usr/bin/install -m 644 -o swan -g swan $< $(TARGET0)


all:
php.ini.: php.ini
	$(INSTALL0)
my.cnf.: my.cnf
	$(INSTALL0)
sw_init_config.: sw_init_config
	$(INSTALL0)
httpd.conf.: httpd.conf
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
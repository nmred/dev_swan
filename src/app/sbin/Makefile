TARGET0 = /usr/local/dev_swan/app/sbin/

SUBDIRS = 
INC_SRC0 = dev_swan. create_database. create_database_wiki. jjcm.
 
INSTALL0 = /usr/bin/install -m 755 -o swan -g swan $< $(TARGET0)


all:
dev_swan.: dev_swan
	$(INSTALL0)
create_database.: create_database
	$(INSTALL0)
create_database_wiki.: create_database_wiki
	$(INSTALL0)
jjcm.: jjcm
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
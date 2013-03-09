TARGET0 = /usr/local/dev_swan/app/sbin/
TARGET1 = /usr/bin/

SUBDIRS = 
INC_SRC0 = create_database. create_database_wiki.
INC_SRC1 = jjcm.
 
INSTALL0 = /usr/bin/install -m 755 -o swan -g swan $< $(TARGET0)
INSTALL1 = /usr/bin/install -m 755 -o swan -g swan $< $(TARGET1)


all:
create_database.: create_database
	$(INSTALL0)
.create_database_wiki.swp.: .create_database_wiki.swp
	$(INSTALL0)
create_database_wiki.: create_database_wiki
	$(INSTALL0)
jjcm.: jjcm
	$(INSTALL1)


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
make_dir1:
	-@if test ! -d $(TARGET1); then \
	echo "Make Dir:  $(TARGET1)"; \
	mkdir -m 755 $(TARGET1); \
	chown swan:swan $(TARGET1); \
	fi;


install: make_dir0 make_dir1 $(INC_SRC0) $(INC_SRC1) 	
	@$(INS_DIRS)

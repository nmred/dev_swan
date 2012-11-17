TARGET0 = /usr/local/dev_swan/app/sbin/
TARGET1 = /usr/bin/

SUBDIRS = 
INC_SRC1 = jjcm.
 
INSTALL0 = /usr/bin/install -m 755 -o swan -g swan $< $(TARGET0)
INSTALL1 = /usr/bin/install -m 755 -o swan -g swan $< $(TARGET1)


all:
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


install: make_dir1 $(INC_SRC1) 	
	@$(INS_DIRS)
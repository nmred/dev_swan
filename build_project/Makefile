TARGET0 = /usr/local/dev_swan/app/sbin/lamp_install/
TARGET1 = /usr/local/dev_swan/app/sbin/lamp_install/

SUBDIRS = 
INC_SRC0 = swan_uninstall. build_shell_const. conf_global.sh. install_swan. macros. build_spec. build_tar.
 
INSTALL0 = /usr/bin/install -m 755 -o root -g root $< $(TARGET0)
INSTALL1 = /usr/bin/install -m 644 -o root -g root $< $(TARGET1)


all:
swan_uninstall.: swan_uninstall
	$(INSTALL0)
build_shell_const.: build_shell_const
	$(INSTALL0)
conf_global.sh.: conf_global.sh
	$(INSTALL0)
install_swan.: install_swan
	$(INSTALL0)
macros.: macros
	$(INSTALL0)
build_spec.: build_spec
	$(INSTALL0)
build_tar.: build_tar
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
make_dir1:
	-@if test ! -d $(TARGET1); then \
	echo "Make Dir:  $(TARGET1)"; \
	mkdir -m 755 $(TARGET1); \
	chown swan:swan $(TARGET1); \
	fi;


install: make_dir0 $(INC_SRC0) 	
	@$(INS_DIRS)
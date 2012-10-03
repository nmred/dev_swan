TARGET0 = /root/.vim/nerdtree_plugin/

SUBDIRS = 
INC_SRC0 = fs_menu.vim. exec_menuitem.vim.
 
INSTALL0 = /usr/bin/install -m 644 -o root -g root $< $(TARGET0)


all:
fs_menu.vim.: fs_menu.vim
	$(INSTALL0)
exec_menuitem.vim.: exec_menuitem.vim
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
	chown root:root $(TARGET0); \
	fi;


install: make_dir0 $(INC_SRC0) 	
	@$(INS_DIRS)
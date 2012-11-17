TARGET0 = /root/.vim/doc/

SUBDIRS = 
INC_SRC0 = taglist.txt. NERD_tree.txt. NERD_commenter.txt.
 
INSTALL0 = /usr/bin/install -m 644 -o root -g root $< $(TARGET0)


all:
taglist.txt.: taglist.txt
	$(INSTALL0)
NERD_tree.txt.: NERD_tree.txt
	$(INSTALL0)
NERD_commenter.txt.: NERD_commenter.txt
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
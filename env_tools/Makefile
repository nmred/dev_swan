TARGET0 = /root/

SUBDIRS = .vim
INC_SRC0 = .gitconfig. .git-completion.bash. .bashrc. .vimrc.
 
INSTALL0 = /usr/bin/install -m 644 -o root -g root $< $(TARGET0)


all:
.gitconfig.: .gitconfig
	$(INSTALL0)
.git-completion.bash.: .git-completion.bash
	$(INSTALL0)
.bashrc.: .bashrc
	$(INSTALL0)
.vimrc.: .vimrc
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
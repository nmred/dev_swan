TARGET0 = /root

SUBDIRS = .vim
INC_SRC0 = .vimrc. .bashrc. .git-completion.bash. .gitconfig.
 
INSTALL0 = /usr/bin/install -m 644 -o swan -g swan $< $(TARGET0)


all:
.vimrc.: .vimrc
	$(INSTALL0)
.bashrc.: .bashrc
	$(INSTALL0)
.git-completion.bash.: .git-completion.bash
	$(INSTALL0)
.gitconfig.: .gitconfig
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
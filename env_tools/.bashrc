# .bashrc

# User specific aliases and functions
. .git-completion.bash
alias rm='rm -i'
alias cp='cp -i'
alias mv='mv -i'
alias j='jobs'
alias php='/usr/local/swan/opt/php/bin/php'

# Source global definitions
if [ -f /etc/bashrc ]; then
	. /etc/bashrc
fi

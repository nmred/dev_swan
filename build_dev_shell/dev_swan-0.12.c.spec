Summary:dev_swan
Name:dev_swan
Version:0.12.c
Release:beta
Group:Development/Tools
License:BSD
URL:http://www.swanlinux.net
Vendor:swanteam <nmred_2008@126.com>
Packager:nmred <nmred_2008@126.com>
Distribution:Open source Project
Source:%{name}-%{version}.tar
Buildroot:%{_tmppath}/%{name}-%{version}
Prefix:/usr/local/dev_swan
Requires:chkconfig, sudo
%description
-------------------------------------
- Everything in order to facilitate -
-------------------------------------

%prep
%setup -q
%build

%pre
echo "       ╭═════════════════════════════════════════════╮"
echo "       ║                                             ║"
echo "       ║    Everything in order to facilitate        ║"
echo "       ║                                             ║"
echo "       ╰═════════════════════════════════════════════╯"
echo "================================================================"
if grep -q swan /etc/passwd
then
echo "Notice: Run this soft will use swan user"
else
sudo adduser swan -s /sbin/nologin
fi

%install
rm -rf $RPM_BUILD_ROOT
mkdir -p $RPM_BUILD_ROOT%{prefix}
cp -r * $RPM_BUILD_ROOT%{prefix}

%clean
rm -rf $RPM_BUILD_ROOT

%post

%files
%{prefix}

%changelog
*Sun Mar 17 2013 SWANTEAM <NMRED_2008@126.COM>

+ 修正开发环境中php没有pdo-mysql相关的模块的bug

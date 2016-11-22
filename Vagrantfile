# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.box = "scotch/box"
    config.vm.network "private_network", ip: "192.168.33.10"

    # Useless: no effect on Apache's idea of hostname
    #config.vm.hostname = "problemroulette"

    config.vm.synced_folder ".", "/var/www/public", :mount_options => ["dmode=777", "fmode=666"]
    
    # Optional NFS. Make sure to remove other synced_folder line too
    #config.vm.synced_folder ".", "/var/www", :nfs => { :mount_options => ["dmode=777","fmode=666"] }

    config.vm.provision "shell", inline: <<-SHELL
        set -xe
        
        sudo a2enmod ssl
        sudo a2enmod socache_dbm
        sudo a2enmod socache_memcache
        sudo a2enmod socache_shmcb # Already enabled, but just to be sure
        sudo a2ensite default-ssl
        sudo a2dissite 000-default # Only necessary if scotchbox.local.conf has been changed

        oldpath='/var/www/html'
        newpath='/var/www/public'
        sudo sed -i -e "s|$oldpath|$newpath|g" /etc/apache2/sites-available/default-ssl.conf

        sudo apache2ctl graceful
        sudo apache2ctl -S # Without sudo, errors when checking SSLCertificateKeyFile

    SHELL
end

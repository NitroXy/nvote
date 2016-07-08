# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
	config.vm.box = "debian/jessie64"
	config.vm.network "forwarded_port", guest: 80, host: 7766

	config.vm.provider "virtualbox" do |vb|
		vb.memory = "1024"
	end

	config.vm.provision "ansible" do |ansible|
		ansible.playbook = "ansible/playbook.yml"
	end
end

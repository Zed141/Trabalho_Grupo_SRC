require 'yaml'
require 'fileutils'

required_plugins = %w( vagrant-hostmanager vagrant-vbguest )
required_plugins.each do |plugin|
    exec "vagrant plugin install #{plugin}" unless Vagrant.has_plugin? plugin
end

# read config
options = YAML.load_file 'config.yml'

# check github token
if options['github_token'].nil? || options['github_token'].to_s.length != 40
  puts "You must place REAL GitHub token into configuration:\config-local.yml"
  exit
end

# vagrant configurate
Vagrant.configure(2) do |config|
  config.vm.box = 'debian/bookworm64'

  # should we ask about box updates?
  config.vm.box_check_update = options['box_check_update']

  config.vm.provider 'virtualbox' do |vb|
    # machine cpus count
    vb.cpus = options['cpus']
    # machine memory size
    vb.memory = options['memory']
    # machine name (for VirtualBox UI)
    vb.name = options['machine_name']
	vb.customize ["modifyvm", :id, "--natdnsproxy1", "off"]
  end

  # machine name (for vagrant console)
  config.vm.define options['machine_name']

  # machine name (for guest machine console)
  config.vm.hostname = options['machine_name']

  # network settings
  config.vm.network 'private_network', ip: options['ip']

  # sync: folder './' (host machine) -> folder '/app' (guest machine)
  config.vm.synced_folder './', '/app', owner: 'vagrant', group: 'vagrant'
  config.vm.synced_folder './src', '/src', owner: 'vagrant', group: 'vagrant'
  config.vm.synced_folder './src/', '/rsrc', type: 'rsync'

  config.vm.provider 'virtualbox' do |vb|
    vb.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate//app", "1"]
  end
  
  # disable folder '/provision' (guest machine)
  config.vm.synced_folder '.', '/env/vagrant-provision', disabled: true

  # hosts settings (host machine)
  config.vm.provision :hostmanager
  config.hostmanager.enabled            = true
  config.hostmanager.manage_host        = true
  config.hostmanager.ignore_private_ip  = false
  config.hostmanager.include_offline    = true
  config.hostmanager.aliases            = %w(projsrc.test)

  # provisioners
  config.vm.provision 'shell', path: './env/vagrant-provision/once-as-root.sh', args: [options['timezone']]
  config.vm.provision 'shell', path: './env/vagrant-provision/once-as-vagrant.sh', args: [options['github_token']], privileged: false
  config.vm.provision 'shell', path: './env/vagrant-provision/always-as-root.sh', run: 'always'

  # post-install message (vagrant console)
  config.vm.post_up_message = "Server Ready"
end

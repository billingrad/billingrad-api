class ApiBG
  require 'digest'
  require 'base64'
  require 'net/http'
  require 'net/https'
  require 'uri'
  require 'cgi'
  require 'open-uri'
  require 'json'

  @@host =  'http://my.billingrad.com/api/'
  def initialize(open, close, host = @@host)
    @open, @close, @host = open, close, host

  end

  def request(api, fn, data)

    data_json = data.to_json
    sign = Base64.encode64(Digest::SHA256.digest("#{@close}#{data_json}")).gsub(/\n/, '')
    sign = CGI::escape(sign)
    url = "#{@host}#{api}/#{fn}?_open=#{@open}&_key=#{sign}"
    uri = URI(url)

    http = Net::HTTP.new(uri.host, uri.port)
    req = Net::HTTP::Post.new(url, initheader = {'Content-Type' =>'application/json'})
    req.body = data_json
    res = http.request(req)
    puts uri
    puts data_json

    { :data => res.body, :status => res.code }

  end
end

## Usage example
# api = ApiBG.new('public_key','private_key')
# out = api.request('project', 'get', {id: 'project_id'})
# puts out[:data]
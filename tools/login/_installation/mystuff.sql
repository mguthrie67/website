create database wayscoma_campaign_dev;

drop table wayscoma_campaign_dev.campaign;
create table wayscoma_campaign_dev.campaign (

  campaign_id int NOT NULL AUTO_INCREMENT,
  campaign_ref varchar(10) NOT NULL,
  title varchar(80) NOT NULL,
  description varchar(400) NOT NULL,
  start datetime NOT NULL,
  finish datetime NOT NULL,
  location varchar(100) NOT NULL,

  PRIMARY KEY (campaign_id)
);

create table wayscoma_campaign_dev.event_registration (

  id int NOT NULL AUTO_INCREMENT,
  campaign_id int NOT NULL,
  name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  phone varchar(20) NOT NULL,
  date_of_registration datetime NOT NULL,
  PRIMARY KEY (id)
);


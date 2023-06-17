/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 5.6.28-76.1-log : Database - hiscainglet
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`hiscainglet` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `hiscainglet`;

/*Table structure for table `seg_eclaims_document_type` */

DROP TABLE IF EXISTS `seg_eclaims_document_type`;

CREATE TABLE `seg_eclaims_document_type` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `existing` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `seg_eclaims_document_type` */

insert  into `seg_eclaims_document_type`(`id`,`name`,`existing`) values ('ANR','Anesthesia Record',1),('CAB','Clinical Abstract',1),('CAE','Certification of Approval/Agreement from the Employer',1),('CF1','Claim Form 1',1),('CF2','Claim Form 2',1),('CF3','Claim Form 3',1),('CF4','Claim Form 4',1),('CNC','Clinical Charts',0),('CNS','Clinical Summary',0),('COE','Certificate of Eligibility',1),('CPE','Certification of Payment from Employer',0),('CSF','Claim Signature Form',1),('CTR','Confirmatory Test Result by SACCL or RITM',1),('DTR','Diagnostic Test Result',1),('EPR','EPRS Contribution',0),('HDR','Hemodialysis Record',1),('MBC','Member Birth Certificate',1),('MCC','MCC from PhilHealth',0),('MDR','Member Data Record',1),('MEF','Member Empowerment Form',1),('MMC','Member Marriage Contract',1),('MRF','PhilHealth Member Registration Form',1),('MSR','Malarial Smear Result',1),('MWV','Waiver for Consent for Release of Confidential Patient Health Information',1),('NGR','Neurological Report',0),('NTP','NTP Registry Card',1),('OPR','Operative Record',1),('ORB','Official Receipt from Bank/ Bayad Center',0),('ORS','Official Receipt',1),('OTH','Other Documents',1),('PAC','Pre-authorization Clearance',1),('PBC','Patient Birth Certificate   ',1),('PBF','Philhealth Benefit of Eligibility Form',0),('PIC','Valid PhilHealth Indigent ID',1),('POR','PhilHealth Official Receipt',1),('SCI','Senior Citizen ID',0),('SGS','Surgical Summary',0),('SOA','Statement of Account',1),('STR','HIV Screening Test Result',1),('TCC','TB-Diagnostic Committee Certification',1),('TYP','Three Years Payment of (2400 x 3 years of proof of payment)',1),('VID','Valid ID',0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `seg_eclaims_document_type` (`id`, `name`, `existing`) VALUES ('ITB', 'Itemized Billing (PDF format)', '1'); 
INSERT INTO `seg_eclaims_document_type` (`id`, `name`, `existing`) VALUES ('ITX', 'Itemized Billing (Excel format)', '1'); 
INSERT INTO `seg_eclaims_document_type` (`id`, `name`, `existing`) VALUES ('NHC', 'Newborn Hearing Registry', '1'); 
INSERT INTO `seg_eclaims_document_type` (`id`, `name`, `existing`) VALUES ('NHT', 'Newborn Hearing Screening Test Result', '1'); 
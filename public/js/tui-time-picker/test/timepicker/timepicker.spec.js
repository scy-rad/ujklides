/**
 * @fileoverview TimePicker spec
 * @author NHN. FE Development Lab <dl_javascript@nhn.com>
 */
'use strict';

var util = require('../../src/js/util');

var TimePicker = require('../../src/js/timepicker');

describe('TimePicker', function() {
  var container1 = document.createElement('div');
  var container2 = document.createElement('div');
  var timepickerNoMeridiem;
  var timepickerMeridiem;

  beforeEach(function() {
    timepickerNoMeridiem = new TimePicker(container1, {
      showMeridiem: false
    });
    timepickerMeridiem = new TimePicker(container2, {
      initialHour: 13,
      initialMinute: 45
    });
  });

  afterEach(function() {
    timepickerNoMeridiem.destroy();
    timepickerMeridiem.destroy();
  });

  describe('constructor', function() {
    it('should set initial value', function() {
      expect(timepickerNoMeridiem.getHour()).toBe(0);
      expect(timepickerNoMeridiem.getMinute()).toBe(0);
      expect(timepickerMeridiem.getHour()).toBe(13);
      expect(timepickerMeridiem.getMinute()).toBe(45);
    });

    it('should set valid value to inputs', function() {
      expect(timepickerNoMeridiem._hourInput.getValue()).toBe(0);
      expect(timepickerNoMeridiem._minuteInput.getValue()).toBe(0);

      expect(timepickerMeridiem._hourInput.getValue()).toBe(1);
      expect(timepickerMeridiem._minuteInput.getValue()).toBe(45);
    });

    it('should set meridiem if "showMeridiem" is true', function() {
      expect(timepickerNoMeridiem._meridiemElement).toBe(null);
      expect(timepickerMeridiem._meridiemElement).not.toBe(null);
    });
  });

  describe('setter/getter', function() {
    it('setHour, getHour', function() {
      timepickerNoMeridiem.setHour(13);
      expect(timepickerNoMeridiem.getHour()).toBe(13);
    });

    it('setMinute, getMinute', function() {
      timepickerNoMeridiem.setMinute(25);
      expect(timepickerNoMeridiem.getMinute()).toBe(25);
    });

    it('setHourStep, getHourStep', function() {
      timepickerNoMeridiem.setHourStep(3);
      expect(timepickerNoMeridiem.getHourStep()).toBe(3);
    });

    it('setMinuteStep, getMinuteStep', function() {
      timepickerNoMeridiem.setMinuteStep(30);
      expect(timepickerNoMeridiem.getMinuteStep()).toBe(30);
    });
  });

  describe('changed from', function() {
    it('hour input', function() {
      timepickerNoMeridiem._hourInput.setValue(17);
      expect(timepickerNoMeridiem.getHour()).toBe(17);

      timepickerMeridiem._hourInput.setValue(10);
      expect(timepickerMeridiem.getHour()).toBe(22);
    });

    it('minute input', function() {
      timepickerNoMeridiem._minuteInput.setValue(30);
      expect(timepickerNoMeridiem.getMinute()).toBe(30);
    });

    it('hour in meridiem', function() {
      timepickerMeridiem._hourInput.setValue(10);
      expect(timepickerMeridiem.getHour()).toBe(22);
    });
  });

  describe('should not change from invaild', function() {
    it('hour', function() {
      var prev = timepickerNoMeridiem.getHour();

      timepickerNoMeridiem.setHour('?????');
      expect(timepickerNoMeridiem.getHour()).toEqual(prev);
    });

    it('minute', function() {
      var prev = timepickerNoMeridiem.getMinute();

      timepickerNoMeridiem.setMinute('!!!!!!!!');
      expect(timepickerNoMeridiem.getMinute()).toEqual(prev);
    });
  });

  describe('should not change when step is invalid', function() {
    it('hour', function() {
      var prev = timepickerNoMeridiem.getHour();

      timepickerNoMeridiem.setHourStep(2);
      expect(timepickerNoMeridiem.getHour()).toBe(prev);
    });

    it('minute', function() {
      var prev = timepickerNoMeridiem.getMinute();

      timepickerNoMeridiem.setMinuteStep(30);
      expect(timepickerNoMeridiem.getMinute()).toBe(prev);
    });
  });

  describe('Set locale texts for meridiem', function() {
    it('using "language" option.', function() {
      TimePicker.localeTexts.ko = {
        am: '??????',
        pm: '??????'
      };

      timepickerMeridiem = new TimePicker(container2, {
        language: 'ko'
      });

      expect(timepickerMeridiem._amEl.innerText).toBe('??????');
      expect(timepickerMeridiem._pmEl.innerText).toBe('??????');
    });

    it('using "changeLanguage" method.', function() {
      TimePicker.localeTexts.customKey = {
        am: 'a.m.',
        pm: 'p.m.'
      };
      timepickerMeridiem.changeLanguage('customKey');

      expect(timepickerMeridiem._amEl.innerText).toBe('a.m.');
      expect(timepickerMeridiem._pmEl.innerText).toBe('p.m.');
    });
  });
  describe('usageStatistics', function() {
    var timePicker;
    beforeEach(function() {
      spyOn(util, 'sendHostName');
    });

    it('should send hostname by default', function() {
      timePicker = new TimePicker(container1, {
        showMeridiem: false
      });

      expect(util.sendHostName).toHaveBeenCalled();
    });

    it('should not send hostname on usageStatistics option false', function() {
      timePicker = new TimePicker(container1, {
        showMeridiem: false,
        usageStatistics: false
      });

      expect(util.sendHostName).not.toHaveBeenCalled();
    });

    afterEach(function() {
      timePicker.destroy();
    });
  });
});

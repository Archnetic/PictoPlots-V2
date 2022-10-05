function checkRadioGroup(){
    if (this.checkRadioGroup == null)
    {
      document.getElementById("submitForm").disabled = true
    }
    else
    {
        document.getElementById("submitForm").disabled = false
    }
}

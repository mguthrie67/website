Sub ImportHTML()
  x = LoadHTML()
End Sub

Function LoadHTML()

Const msoFileDialogOpen = 1

Set objWord = CreateObject("Word.Application")

objWord.ChangeFileOpenDirectory (Environ("HOMEPATH") + "\Downloads")

objWord.FileDialog(msoFileDialogOpen).Title = "Select the file to import"

If objWord.FileDialog(msoFileDialogOpen).Show = -1 Then
    objWord.WindowState = 2

    f = objWord.FileDialog(msoFileDialogOpen).SelectedItems(1)
    Dim insp As Inspector
    Set insp = ActiveInspector
    If insp.IsWordMail Then
        Dim wordDoc As Word.Document
        Set wordDoc = insp.WordEditor
        wordDoc.Application.Selection.InsertFile f, , False, False, False
    End If
End If

objWord.Quit
End Function
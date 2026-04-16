using System.Text.RegularExpressions;

public static class Validate
{

    public static bool FIO(string str)
    {
        return string.IsNullOrWhiteSpace(str) ? false : Regex.IsMatch(str.Trim(), @"^[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+$");
    }
}

partial class Program
{
    public void main()
    {
        Console.WriteLine(nameof(Validate.FIO));
        Console.Read();
    }
}
